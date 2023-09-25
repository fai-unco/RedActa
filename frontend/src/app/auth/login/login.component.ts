import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { BehaviorSubject, catchError, finalize, of, throwError } from 'rxjs';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {

  loginForm:FormGroup;
  error: string = "";
  loading: boolean = false;

  constructor(private fb:FormBuilder, private authService: AuthService, private router: Router) {
    this.loginForm = this.fb.group({
        email: ['', Validators.required],
        password: ['', Validators.required]
    });
  }

  login() {
    const formValues = this.loginForm.value;
    this.error = "";
    if (this.loginForm.valid) {
      this.loading = true;
      this.authService.login(formValues.email, formValues.password) 
      .pipe(finalize(()=> this.loading = false))
      .subscribe({
        next: (res) => {
          this.authService.setSession(res.data);
        },
        error: (e) => {
          if(e.status == '401'){
            this.error = e.error.message;
          } else {
            this.error = 'Hubo un error. Intente nuevamente';
          }
        }
      });
    }
  }

}