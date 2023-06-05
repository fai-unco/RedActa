import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
     
    constructor(private http: HttpClient, private router: Router) {}

    login(email:string, password:string ): Observable<any> {
        return this.http.post(environment.API_URL_BASE + '/login', {email, password})
    }
        
    setSession(authResult: any) {
        localStorage.setItem('access_token', authResult.access_token);
        this.router.navigate(['/'])
    }          

    logout() {
        this.http.post(environment.API_URL_BASE + '/logout',{}).subscribe({
            next: _ => {
                localStorage.removeItem("access_token");
                this.router.navigate(['/login']);
            },
            error: e => {
                console.log(e);
            }
        }) 
    }

    public isLoggedIn() {
        let token = localStorage.getItem("access_token");
        return token != null && token.length > 0;
    }
}
          
    