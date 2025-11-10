import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { NbDialogService } from '@nebular/theme';
import { EditHeadingDialogComponent } from './edit-heading-dialog/edit-heading-dialog.component';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { finalize  } from 'rxjs';
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
  heading! :any;
  issuers: any [] = [];
  selectedIssuerId: any;

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
      context: { heading, issuers },
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
        this.loadIssuers();
      }
    });
  }

  selectIssuer(newIssuerId: any) {
    this.loading = true;
    this.selectedIssuerId = newIssuerId;
    this.api.get(`headings?issuerId=${this.selectedIssuerId}&includeFile=true`)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => this.headings = res.data,
        error: e => this.errorHandler.handle(e)
      });
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
}
