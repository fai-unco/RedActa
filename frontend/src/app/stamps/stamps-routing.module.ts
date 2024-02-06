import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { StampsManagerComponent } from './stamps-manager/stamps-manager.component';

const routes: Routes = [{ path: '', component: StampsManagerComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StampsRoutingModule { }
