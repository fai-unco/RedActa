import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  { 
    path: 'login', loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule) 
  },
  { 
    path: '', loadChildren: () => import('./panel/panel.module').then(m => m.PanelModule) 
  },
]
@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
