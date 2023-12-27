import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, catchError, of, throwError } from 'rxjs';
import { AuthService } from './auth.service';
import { Router } from '@angular/router';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(private authService: AuthService, private router: Router) {}

  private handleAuthError(err: HttpErrorResponse): Observable<any> {
    //handle your auth error or rethrow
    if ((err.status === 401 || err.status === 403) && this.router.url != '/login') {
      this.authService.logout();
      // if you've caught / handled the error, you don't want to rethrow it unless you also want downstream consumers to have to handle it as well.
      return of();
    }
    return throwError(()=> err);
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
      //Clone the request to add the new header.
      const authReq = req.clone({headers: req.headers.set("Authorization", "Bearer " + localStorage.getItem("access_token"))});
      // catch the error, make specific functions for catching specific errors and you can chain through them with more catch operators
      return next.handle(authReq).pipe(catchError(err=> this.handleAuthError(err))); //here use an arrow function, otherwise you may get "Cannot read property 'navigate' of undefined" on angular 4.4.2/net core 2/webpack 2.70
  }
}
