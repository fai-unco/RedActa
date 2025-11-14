import { Component, Inject, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl } from '@angular/forms';
import { NbDialogRef, NB_DIALOG_CONFIG } from '@nebular/theme';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { finalize } from 'rxjs';


@Component({
  selector: 'app-edit-user-dialog',
  templateUrl: './edit-user-dialog.component.html',
})
export class EditUserDialogComponent implements OnInit {
  userForm!: FormGroup;
  @Input() user: any;
  @Input() allowRolesSelection: boolean = true;
  loading: boolean = false;
  saveSuccess = false;
  successMsg = '';
  @Input() roles: any[] = [];
  showPassword = false;
  showPasswordConfirmation = false;

  constructor(
    protected dialogRef: NbDialogRef<EditUserDialogComponent>,
    private fb: FormBuilder,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) {}

  ngOnInit(): void {
    if (this.allowRolesSelection) {
      if (this.roles.length === 0) {
        this.loading = true;
        this.api.get('roles')
          .pipe(finalize(() => { this.loading = false }))
          .subscribe({
            next: (res: any) => {
              this.roles = res.data;
            },
            error: e => this.errorHandler.handle(e)
          });
      }
    }
    this.userForm = this.fb.group({
      email: [this.user? this.user.email : '', [Validators.required, Validators.email]],
    });
    if (this.user) {
      this.userForm.addControl('name', this.fb.control(this.user.name, Validators.required));
      this.userForm.addControl('lastName', this.fb.control(this.user.lastName, Validators.required));
      this.userForm.addControl('role', this.fb.control(this.user.roles[0].name, Validators.required));
      this.userForm.addControl('password', this.fb.control(''));
      this.userForm.addControl('passwordConfirmation', this.fb.control(''));
      this.userForm.get('password')?.setValidators([Validators.minLength(8)]);
      this.userForm.get('passwordConfirmation');
      this.userForm.setValidators([this.passwordsMatchValidator]);
      this.successMsg = 'Actualización exitosa!';
    } else {
      this.successMsg = 'Se ha enviado un correo al usuario con las instrucciones para crear su cuenta';
    }
  }

  passwordsMatchValidator(form: AbstractControl) {
    const password = form.get('password')?.value;
    const confirm = form.get('passwordConfirmation')?.value;
    return password === confirm ? null : { passwordMismatch: true };
  }

  getPasswordFieldType(field: string) {
    let showField = field === 'password' ? this.showPassword : this.showPasswordConfirmation;
    if (showField) {
      return 'text';
    }
    return 'password';
  }

  togglePasswordVisibility(field: string) {
    if (field === 'password') {
      this.showPassword = !this.showPassword;
    } else {
      this.showPasswordConfirmation = !this.showPasswordConfirmation;
    }
  }

  submit() {
    this.loading = true;
    //Remove all fields that are empty
    for (const key in this.userForm.value) {
      if (this.userForm.value[key] === '') {
        delete this.userForm.value[key];
      }
    }
    const data = this.userForm.value;
    let request = 
      this.user? this.api.patch('redacta_users', this.user.id, data):
      this.api.post('signup_invitations', data);
    request.pipe(finalize(() => { this.loading = false }))
      .subscribe({
        next: () => {
          this.saveSuccess = true;
          setTimeout(() => {
            this.close();
          }, 4000);
        },
        error: (error) => this.errorHandler.handle(error)  
      });
  } 
  
  close() {
    this.dialogRef.close(this.saveSuccess)
  }
}