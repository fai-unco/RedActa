import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthInterceptor } from './auth.interceptor';
import { AuthGuard } from './auth.guard';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthService } from './auth.service';

@NgModule({
  declarations: [
    AuthInterceptor,
    AuthGuard,
    AuthService
  ],
  imports: [
    CommonModule
  ],
  exports: [
    AuthInterceptor,
    AuthGuard,
    AuthService
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true }
  ]

})
export class AuthCoreModule { }
