import { Component, OnInit } from '@angular/core';
import { TenantService } from '../../../app/core/tenant.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-tenant-list',
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {
  tenants: any[] = [];

  constructor(private tenantService: TenantService, private router: Router) {}

  ngOnInit(): void {
    this.fetchTenants();
  }

  fetchTenants(): void {
    this.tenantService.getAll().subscribe((res: any) => {
      this.tenants = res.data;
    });
  }

  edit(id: number): void {
    this.router.navigate(['/tenants/edit', id]);
  }

  delete(id: number): void {
    if (confirm('Are you sure to delete?')) {
      this.tenantService.delete(id).subscribe(() => {
        this.fetchTenants();
      });
    }
  }
}
