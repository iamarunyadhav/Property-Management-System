import { ReactiveFormsModule } from '@angular/forms';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PropertiesRoutingModule } from './properties-routing.module';

@NgModule({
  declarations: [ListComponent, FormComponent],
  imports: [
    CommonModule,
    PropertiesRoutingModule,
    ReactiveFormsModule
  ]
})
export class PropertiesModule { }
