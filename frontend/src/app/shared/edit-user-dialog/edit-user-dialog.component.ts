import { Component, Inject, Input, OnInit, Output, EventEmitter, Optional } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl } from '@angular/forms';
import { NbDialogRef, NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-edit-user-dialog',
  templateUrl: './edit-user-dialog.component.html',
})
export class EditUserDialogComponent implements OnInit {
  userForm!: FormGroup;
  @Input() allowRolesSelection: boolean = true;
  @Input() roles: any[] = [];
  // Si se proporciona un objeto de usuario, se utiliza para inicializar el formulario. 
  // Puede existir o no, dependiendo de si se está creando un nuevo usuario o editando uno existente.
  @Input() user: any;
  // Si se proporciona un userId, se utiliza para obtener los datos del usuario desde la API
  @Input() userId?: any;
  @Input() invitationToken?: string; // agregado: token para el registro de usuario
  @Output() saved = new EventEmitter<any>(); // agregado: emite el payload al padre
  loading: boolean = false;
  saveSuccess = false;
  successMsg = '';
  showPassword = false;
  showPasswordConfirmation = false;

  constructor(
    @Optional() protected dialogRef: NbDialogRef<EditUserDialogComponent> | null,
    private fb: FormBuilder,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) {}

  ngOnInit(): void {
    // Si se proporciona un userId, obtenemos los datos del usuario desde la API
    if (this.userId && !this.user) {
      this.loading = true;
      this.api.get('redacta_users', this.userId)
        .pipe(finalize(() => { this.loading = false }))
        .subscribe({
          next: (res: any) => {
            this.user = res.data;
          },
          error: e => this.errorHandler.handle(e)
        });
    }
    this.userForm = this.fb.group({
      email: [this.user.email ?? '', [Validators.required, Validators.email]],
      name: [this.user.name ?? '', Validators.required],
      lastName: [this.user.lastName ?? '', Validators.required],
      password: ['', [Validators.minLength(8)]],
      passwordConfirmation: ['', [Validators.minLength(8)]],
    });
    if (this.allowRolesSelection) {
      this.userForm.addControl('roleId', this.fb.control(this.user.roles ? this.user.roles[0].id : '', Validators.required));
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
    this.userForm.setValidators([this.passwordsMatchValidator]);
    this.successMsg = 'Actualización exitosa!';
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
    const payload: any = { ...this.userForm.value };
    for (const key in payload) {
      if (payload[key] === '') {
        delete payload[key];
      }
    }

    //If user is being edited => PATCH to redacta_users/:id
    if (this.user.id) {
      this.api.patch('redacta_users', this.user.id, payload)
        .pipe(finalize(() => { this.loading = false }))
        .subscribe({
          next: _ => {
            this.saveSuccess = true;
            setTimeout(() => this.close(), 2000);
          },
          error: (error) => { this.errorHandler.handle(error); }
        });
      return;
    } else {
       this.api.post('register', {...payload, token: this.invitationToken})
        .pipe(finalize(() => { this.loading = false }))
        .subscribe({
          next: (res: any) => {
            this.saveSuccess = true;
            this.saved.emit(true);
            if (this.dialogRef) this.dialogRef.close(true);
            setTimeout(() => this.close(), 2000);
          },
          error: (error) => { this.errorHandler.handle(error); }
        });
    }
    /*this.loading = false;
    this.saveSuccess = true;
    this.saved.emit(payload);*/
  }

  close() {
    if (this.dialogRef) {
      this.dialogRef.close(this.saveSuccess);
    }
  }
}