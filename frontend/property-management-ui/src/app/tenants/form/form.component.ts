import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { TenantService } from '../../../../src/app/core/tenant.service';
import { PropertyService } from '../../../../src/app/core/property.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-tenant-form',
  templateUrl: './form.component.html',
  standalone: true,
  imports: [CommonModule],
})
export class FormComponent implements OnInit {
  form: FormGroup;
  isEdit = false;
  id: number | null = null;
  properties: any[] = [];

  constructor(
    private fb: FormBuilder,
    private tenantService: TenantService,
    private propertyService: PropertyService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.form = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      property_id: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.id = this.route.snapshot.params['id'];
    this.isEdit = !!this.id;

    this.propertyService.getAll().subscribe((res) => {
      this.properties = res.data;
    });

    if (this.isEdit && this.id) {
      this.tenantService.getById(this.id).subscribe((res) => {
        this.form.patchValue(res.data);
      });
    }
  }

  onSubmit(): void {
    if (this.form.invalid) return;

    const action = this.isEdit
      ? this.tenantService.update(this.id!, this.form.value)
      : this.tenantService.create(this.form.value);

    action.subscribe(() => {
      this.router.navigate(['/tenants']);
    });
  }
}
