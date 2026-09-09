import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-add-signup-invitation',
  templateUrl: './add-signup-invitation.component.html',
  styleUrls: ['./add-signup-invitation.component.scss']
})
export class AddSignupInvitationComponent implements OnInit {

  loading: boolean = false;
  signupInvitationForm!: FormGroup;
  success = false;

  constructor(protected dialogRef: NbDialogRef<AddSignupInvitationComponent>,
              protected api: ApiConnectionService,
              protected fb: FormBuilder,
              protected errorHandler: ErrorHandlerService) { }

  ngOnInit(): void {
    this.signupInvitationForm = this.fb.group({
      email: this.fb.control('', [Validators.required, Validators.email]),
    });
  }

  close(): void {
    this.dialogRef.close(this.success);
  }

  submit(): void {
    let request = this.api.post(`signup_invitations`, this.signupInvitationForm.value);
    this.success = false;
    this.loading = true;
    request
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: () => {
          this.success = true
          setTimeout(() => this.close(), 4000);
        },
        error: (error) => this.errorHandler.handle(error)
      });

  }

}
