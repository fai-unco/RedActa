import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { NbDialogService } from '@nebular/theme';
import { EditHeadingDialogComponent } from './edit-heading-dialog/edit-heading-dialog.component';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { finalize  , forkJoin } from 'rxjs';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';

@Component({
  selector: 'app-headings',
  templateUrl: './headings.component.html',
  styleUrls: ['./headings.component.scss']
})
export class HeadingsComponent implements OnInit {

  loading!: boolean;
  headingForm!: FormGroup;
  headings: any [] = [];
  issuers: any [] = [];
  selectedIssuerId: any;
  currentSettings: any = null; // <-- nueva propiedad para guardar settings actuales

  constructor(protected api: ApiConnectionService,
              protected fb: FormBuilder,
              protected errorHandler: ErrorHandlerService,
              protected dialogService: NbDialogService) { }

  ngOnInit(): void {
    this.loadIssuers();
  }

  openEditHeadingDialog(heading: any = null) {
    let issuers = this.issuers;
    this.dialogService.open(EditHeadingDialogComponent, {
      context: { heading, issuers, issuerId: this.selectedIssuerId },
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        this.selectIssuer(this.selectedIssuerId);
      }
    });
  }

  openRemoveHeadingDialog(heading: any) {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma eliminar el membrete <b>${heading.description}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Eliminar',
        apiRoute: 'headings',
        resourceId: heading.id,
        requestType: 'delete',
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((removed: boolean) => {
      if (removed) {
        this.loadHeadings();
      }
    });
  }

  selectIssuer(newIssuerId: any) {
    this.loading = true;
    this.selectedIssuerId = newIssuerId;
    this.loadHeadings();
  }

  loadIssuers() {
    this.loading = true;
    this.api.get('issuers')
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => this.issuers = res.data,
        error: e => this.errorHandler.handle(e)
      });
  }

  loadHeadings() {
    if (this.selectedIssuerId) {
      this.loading = true;
      const headings$ = this.api.get(`headings?issuerId=${this.selectedIssuerId}&includeFile=true`);
      const settings$ = this.api.get(`issuer_settings?issuer_id=${this.selectedIssuerId}`);
      forkJoin([headings$, settings$])
        .pipe(finalize(() => this.loading = false))
        .subscribe({
          next: ([headingsRes, settingsRes]: any) => {
            this.headings = headingsRes.data || [];
            this.currentSettings = settingsRes?.data || null;
          },
          error: e => this.errorHandler.handle(e)
        });
    }
  }
}
