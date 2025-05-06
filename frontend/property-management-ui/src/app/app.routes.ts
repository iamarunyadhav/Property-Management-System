import { Routes } from '@angular/router';
import { AuthGuard } from './core/auth.guard';
import { MainComponent } from './layout/main/main.component';

export const routes: Routes = [
  {
    path: '',
    component: MainComponent,
    canActivate: [AuthGuard],
    children: [
      { path: 'properties', loadChildren: () => import('./properties/properties.module').then(m => m.PropertiesModule) },
      { path: 'tenants', loadChildren: () => import('./tenants/tenants.module').then(m => m.TenantsModule) },
      { path: 'rent', loadChildren: () => import('./rent/rent.module').then(m => m.RentModule) }
    ]
  },
  {
    path: 'auth',
    loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)  // lazy loaded standalone
  }
];
