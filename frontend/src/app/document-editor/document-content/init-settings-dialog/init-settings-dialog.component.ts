import { Component, OnInit, TemplateRef } from '@angular/core';
import { Router } from '@angular/router';
import { NbDialogRef, NbDialogService } from '@nebular/theme';
import { forkJoin } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorDialogComponent } from 'src/app/shared/error-dialog/error-dialog.component';

@Component({
  selector: 'app-init-settings-dialog',
  templateUrl: './init-settings-dialog.component.html',
  styleUrls: ['./init-settings-dialog.component.scss']
})
export class InitSettingsDialogComponent implements OnInit {

  issuers: any;
  documentTypes: any;
  selectedIssuerId!: number;
  selectedDocumentTypeId!: number;
  viewState = 'loading';
  
  constructor(private connectionService: ApiConnectionService, 
              private dialogService: NbDialogService, 
              protected dialogRef: NbDialogRef<InitSettingsDialogComponent>,
              private router: Router) { }

  ngOnInit(): void {
    forkJoin([this.connectionService.get('document_types'), this.connectionService.get('issuers')]).subscribe({
      next: (results: any) => {
        this.documentTypes = results[0].data;
        this.issuers = results[1].data;
        this.viewState = 'rendering';
      },
      error: e => {
        this.errorHandler(e, '/');
      }
    });
  }

  close(status?: boolean){
    if(status){
      this.dialogRef.close({documentTypeId: this.selectedDocumentTypeId, issuerId: this.selectedIssuerId});
    } else {
      this.dialogRef.close();
    }
  }

  private errorHandler(error?: any, urlRedirect?: any) {
    this.openErrorDialog(error.error.message, urlRedirect);
  }

  openErrorDialog (errorMsg: string, urlRedirect?: any){
    this.dialogService.open(ErrorDialogComponent, {
      context: {
        msg: errorMsg,
      },
    })
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigateByUrl(urlRedirect);
      }
    });
  }

}
