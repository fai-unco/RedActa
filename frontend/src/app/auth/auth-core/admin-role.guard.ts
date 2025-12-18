import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class AdminRoleGuard implements CanActivate {

  constructor(private authService: AuthService, private router: Router) {}

  canActivate(): boolean {
    const role = localStorage.getItem('role');
    if (role === 'super_admin' || role === 'local_admin') {
      return true;
    }
    this.router.navigate(['/']);
    return false;
  }
}