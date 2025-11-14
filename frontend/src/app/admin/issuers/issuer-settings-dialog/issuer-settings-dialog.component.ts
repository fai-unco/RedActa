import { Component, Input, OnInit } from '@angular/core';
import { Form, FormBuilder, FormGroup } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { finalize, forkJoin } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-issuer-settings-dialog',
  templateUrl: './issuer-settings-dialog.component.html',
  styleUrls: ['./issuer-settings-dialog.component.scss']
})
export class IssuerSettingsDialogComponent implements OnInit {
  @Input() issuer: any;
  loading: boolean = true;
  settingsForm!: FormGroup;
  operativeSectionBeginnings: any[] = [];
  stamps: any[] = [];
  headings: any[] = [];
  success = false;
  request: any;
  submitRequest: any;

  constructor(protected dialogRef: NbDialogRef<IssuerSettingsDialogComponent>,
              protected api: ApiConnectionService,
              protected fb: FormBuilder,
              protected errorHandler: ErrorHandlerService) {}

  ngOnInit(): void {
    this.submitRequest = 'post'; 
    let requests = [
      this.api.get(`issuer_settings?issuerId=${this.issuer.id}`),
      this.api.get(`operative_section_beginnings?issuerId=${this.issuer.id}`),
      this.api.get(`stamps`),
      this.api.get(`headings?issuerId=${this.issuer.id}`),
    ];
    this.settingsForm = this.fb.group({
      suggestedOperativeSectionBeginningId: this.fb.control(''),
      suggestedTrueCopyStampId: this.fb.control(''),
      suggestedHeadingId: this.fb.control(''),
      suggestedOperativeSectionLastArticle: this.fb.control(''),
      suggestedStartingPhrase: this.fb.control( ''),
      suggestedPartingPhrase: this.fb.control('')
    });
    forkJoin(requests)
    .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.operativeSectionBeginnings = res[1].data;
          this.stamps = res[2].data;
          this.headings = res[3].data;
          if (res[0].data) {
            this.submitRequest = 'patch';
            this.settingsForm = this.fb.group({
              suggestedOperativeSectionBeginningId: this.fb.control(res[0].data.suggestedOperativeSectionBeginning),
              suggestedTrueCopyStampId: this.fb.control(res[0].data.suggestedTrueCopyStampId),
              suggestedHeadingId: this.fb.control(res[0].data.suggestedHeadingId),
              suggestedOperativeSectionLastArticle: this.fb.control(res[0].data.suggestedOperativeSectionLastArticleId),
              suggestedStartingPhrase: this.fb.control(res[0].data.suggestedStartingPhrase),
              suggestedPartingPhrase: this.fb.control(res[0].data.suggestedPartingPhrase)
            });
          }
        },
        error: (error) => this.errorHandler.handle(error)
      }); 
  }

  saveSettings() {
    this.success = false;
    this.loading = true;
    let request = this.submitRequest === 'post' ?
      this.api.post(`issuer_settings`, this.settingsForm.value) :
      this.api.patch(`issuer_settings`, this.issuer.id, this.settingsForm.value);
    //Remove all fields that are empty
    for (const key in this.settingsForm.value) {
      if (!this.settingsForm.value[key]) {
        delete this.settingsForm.value[key];
      }
    }
    request
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: () => {
          this.success = true;
          setTimeout(() => {
            this.close();
          }, 4000);
        },
        error: e => this.errorHandler.handle(e)
      });
  }

  close() {
    this.dialogRef.close();
  }
}