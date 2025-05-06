import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { PropertyService } from '../../../../src/app/core/property.service';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  standalone: true,
  imports: [CommonModule],
})
export class FormComponent implements OnInit {
  form: FormGroup;
  isEdit = false;
  id: number | null = null;

  constructor(
    private fb: FormBuilder,
    private propertyService: PropertyService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.form = this.fb.group({
      name: ['', Validators.required],
      location: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.id = this.route.snapshot.params['id'];
    this.isEdit = !!this.id;

    if (this.isEdit && this.id) {
      this.propertyService.getById(this.id).subscribe((res) => {
        this.form.patchValue(res.data);
      });
    }
  }

  onSubmit(): void {
    if (this.form.invalid) return;

    const action = this.isEdit
      ? this.propertyService.update(this.id!, this.form.value)
      : this.propertyService.create(this.form.value);

    action.subscribe(() => {
      this.router.navigate(['/properties']);
    });
  }
}
