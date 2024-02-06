import { Component, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { NbDialogRef, NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorDialogComponent } from 'src/app/shared/error-dialog/error-dialog.component';

@Component({
  selector: 'app-stamps-manager',
  templateUrl: './stamps-manager.component.html',
  styleUrls: ['./stamps-manager.component.scss']
})
export class StampsManagerComponent implements OnInit {

  form!: FormGroup;
  uploading: boolean = false;
  dialogState: string = 'rendering'; 
  viewState: string = 'loading';
  stamps: any = [];

  constructor(private apiConnectionService: ApiConnectionService, 
              private fb: FormBuilder,
              private dialogService: NbDialogService, 
              private router: Router) { }

  ngOnInit(): void {
    this.form = this.fb.group({
      'description': this.fb.control(''),
      'content': this.fb.control(''),
    });
    this.apiConnectionService.get('stamps')
      .pipe(finalize(() => this.viewState = 'rendering'))
      .subscribe({
        next: (results: any) => {
          this.stamps = results.data;
        },
        error: e => {
          this.errorHandler(e);
        }
      });

  }

  open(dialog: TemplateRef<any>, index?: number) {
    this.form.reset();
    let action = 'create'
    if(index != null){
      this.form.get('description')?.setValue(this.stamps[index].description);
      this.form.get('content')?.setValue(this.stamps[index].content);
      action = 'update';
    }
    this.dialogService.open(dialog, {context: action, closeOnBackdropClick: false });
  }
  
  submit(ref: NbDialogRef<any>, stampId = null){
    this.dialogState = 'loading';
    let request;
    if (stampId){
      request = this.apiConnectionService.patch('stamps', stampId, this.form.value);
    } else {
      request = this.apiConnectionService.post('stamps', this.form.value);
    } 
    request.pipe(finalize(() => this.dialogState = 'rendering'))
      .subscribe({
        next: _ => {
          ref.close();
          this.reload();
        },
        error: e => {
          this.errorHandler(e);
        }
      });
  }

  remove(stampId: any){
    this.viewState = 'loading';
    this.apiConnectionService.delete('stamps', stampId)
      .pipe(finalize(() => this.dialogState = 'rendering'))
      .subscribe({
        next: _ => {
          this.reload();
        },
        error: e => {
          this.errorHandler(e);
        }
      });
  }


  reload() {
    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
    this.router.onSameUrlNavigation = 'reload';
    this.router.navigate(['/sellos']);
  }

  private errorHandler(error?: any, urlRedirect?: any) {
    let message =  error.error.message ? error.error.message : 'Ha habido un error. Pruebe reintentar la operación'
    this.openErrorDialog(message, urlRedirect);
  }

  openErrorDialog (errorMsg: string, urlRedirect?: any){
    this.dialogService.open(ErrorDialogComponent, {
      context: {
        msg: errorMsg,
      },
    })
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigate([urlRedirect]);
      }
    });
  }

}
