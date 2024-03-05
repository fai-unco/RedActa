import { Component, OnInit, TemplateRef } from '@angular/core';
import { Router } from '@angular/router';
import { NbDialogRef, NbDialogService } from '@nebular/theme';
import { forkJoin } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorDialogComponent } from 'src/app/shared/error-dialog/error-dialog.component';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

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
              protected dialogRef: NbDialogRef<InitSettingsDialogComponent>,
              private errorHandler: ErrorHandlerService) { }

  ngOnInit(): void {
    forkJoin([this.connectionService.get('document_types'), this.connectionService.get('issuers')]).subscribe({
      next: (results: any) => {
        this.documentTypes = results[0].data;
        this.issuers = results[1].data;
        this.viewState = 'rendering';
      },
      error: e => {
        this.dialogRef.close();
        this.errorHandler.handle(e, '/');
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

}
