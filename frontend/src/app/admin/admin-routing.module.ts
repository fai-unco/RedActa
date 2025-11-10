import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UsersComponent } from './users/users.component';
import { IssuersComponent } from './issuers/issuers.component';
import { HeadingsComponent } from './headings/headings.component';

const routes: Routes = [
  { path: 'cuentas', component: UsersComponent },
  { path: 'dependencias', component: IssuersComponent },
  { path: 'membretes', component: HeadingsComponent},
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule { }