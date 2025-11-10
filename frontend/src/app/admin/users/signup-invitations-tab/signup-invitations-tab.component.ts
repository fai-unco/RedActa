import { Component, OnInit } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { environment } from '../../../../environments/environment';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';


@Component({
  selector: 'app-signup-invitations-tab',
  templateUrl: './signup-invitations-tab.component.html',
  styleUrls: ['./signup-invitations-tab.component.scss']
})


export class SignupInvitationsTabComponent implements OnInit {
  loading: boolean = true;
  invitations: any[] = [];
  env = environment;
  signupBaseUrl = environment.FRONTEND_URL_BASE + '/registro?token=';
  
  constructor(private api: ApiConnectionService, 
              private errorHandler: ErrorHandlerService,
              private dialogService: NbDialogService) {}

  ngOnInit(): void {
    this.loadInvitations();
  }

  revokeInvitation(invitation: any): void {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma revocar la invitación a <b>${invitation.email}</b>?`,
        submitType: 'success',
        submitBtnLabel: 'Reactivar',
        apiRoute: 'redacta_users',
        resourceId: invitation.id,
        requestType: 'update',
        nestedApiResource: 'reactivate'
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((revoked: boolean) => {
      if (revoked) {
        this.loadInvitations();
      }
    });
  }

  loadInvitations(): void {
    this.loading = true;
    this.api.get('signup_invitations')
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.invitations = res.data;
        },
        error: e => {
          this.errorHandler.handle(e);
        }
      });
  }
}
