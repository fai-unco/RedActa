import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-edit-issuer-dialog',
  templateUrl: './edit-issuer-dialog.component.html',
  styleUrls: ['./edit-issuer-dialog.component.scss']
})
export class EditIssuerDialogComponent implements OnInit {
  @Input() issuer: any;
  loading: boolean = false;
  issuerForm!: FormGroup;
  success = false;
  successMsg! : string;

  constructor(protected dialogRef: NbDialogRef<EditIssuerDialogComponent>,
              protected api: ApiConnectionService,
              protected fb: FormBuilder,
              protected errorHandler: ErrorHandlerService) { }

  ngOnInit(): void {
    this.issuerForm = this.fb.group({
      description: this.fb.control(this.issuer? this.issuer.description : '', [Validators.required]),
      address: this.fb.control(this.issuer? this.issuer.address : ''),
      phone: this.fb.control(this.issuer? this.issuer.phone : ''),
      email: this.fb.control(this.issuer? this.issuer.email : ''),
      websiteUrl: this.fb.control(this.issuer? this.issuer.websiteUrl : ''),
      city: this.fb.control(this.issuer? this.issuer.city : ''),
      province: this.fb.control(this.issuer? this.issuer.province : ''),
      postalCode: this.fb.control(this.issuer? this.issuer.postalCode : ''),
      code: this.fb.control(this.issuer? this.issuer.code : ''),
    });
    this.successMsg = this.issuer ? 'Emisor editado con éxito' : 'Emisor creado con éxito';
  }

  close(): void {
    this.dialogRef.close(this.success);
  }

  saveIssuer(): void {
    let request = this.issuer ? 
      this.api.patch(`issuers`, this.issuer.id, this.issuerForm.value):
      this.api.post(`issuers`, this.issuerForm.value);
    this.success = false;
    this.loading = true;
    //Remove all fields that are empty
    for (const key in this.issuerForm.value) {
      if (!this.issuerForm.value[key]) {
        delete this.issuerForm.value[key];
      }
    }
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
