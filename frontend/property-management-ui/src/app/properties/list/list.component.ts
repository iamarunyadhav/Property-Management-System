import { Component, OnInit } from '@angular/core';
import { PropertyService } from '../../../../src/app/core/property.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  standalone: true,
  imports: [CommonModule],
})
export class ListComponent implements OnInit {
  properties: any[] = [];

  constructor(private propertyService: PropertyService, private router: Router) {}

  ngOnInit(): void {
    this.fetchProperties();
  }

  fetchProperties(): void {
    this.propertyService.getAll().subscribe((res: any) => {
      this.properties = res.data;
    });
  }

  edit(id: number): void {
    this.router.navigate(['/properties/edit', id]);
  }

  delete(id: number): void {
    if (confirm('Are you sure to delete?')) {
      this.propertyService.delete(id).subscribe(() => {
        this.fetchProperties(); // refresh
      });
    }
  }
}
