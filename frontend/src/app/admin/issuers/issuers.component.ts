import { Component, OnInit } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { IssuerSettingsDialogComponent } from './issuer-settings-dialog/issuer-settings-dialog.component';
import { EditIssuerDialogComponent } from './edit-issuer-dialog/edit-issuer-dialog.component';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';

@Component({
  selector: 'app-issuers',
  templateUrl: './issuers.component.html',
  styleUrls: ['./issuers.component.scss']
})
export class IssuersComponent implements OnInit {

  issuers: any[] = [];
  loading = true;
  includeInactive: string = 'false';

  constructor(private api: ApiConnectionService,
              private errorHandler: ErrorHandlerService,
              private dialogService: NbDialogService) { }

  ngOnInit(): void {
    this.loadIssuers();
  }

  loadIssuers(): void {
    this.loading = true;
    this.api.get(`issuers?admin_mode=true&include_inactive=${this.includeInactive}`)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.issuers = res.data;
        },
        error: (error) => this.errorHandler.handle(error)
      });
  }

  openEditIssuerDialog(issuer: any = null): void {
    this.dialogService.open(EditIssuerDialogComponent, {
      context: { issuer },
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        this.loadIssuers();
      }
    });
  }

  openIssuerSettingsDialog(issuer: any): void {
    this.dialogService.open(IssuerSettingsDialogComponent, {
      context: { issuer },
      closeOnBackdropClick: false
    });
  }

  openDeactivateIssuerDialog(issuer: any): void {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma desactivar la dependencia <b>${issuer.description}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Desactivar',
        apiRoute: 'issuers',
        resourceId: issuer.id,
        requestType: 'delete',
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((deactivated: boolean) => {
      if (deactivated) {
        this.loadIssuers
      }
    });
  }

  openReactivateIssuerDialog(issuer: any): void {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma reactivar la dependencia <b>${issuer.description}</b>?`,
        submitType: 'success',
        submitBtnLabel: 'Reactivar',
        apiRoute: 'issuers',
        resourceId: issuer.id,
        requestType: 'update',
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((deactivated: boolean) => {
      if (deactivated) {
        this.loadIssuers();
      }
    });
  }
}
