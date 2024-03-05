import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { NbDialogService } from '@nebular/theme';
import { ErrorDialogComponent } from '../error-dialog/error-dialog.component';

@Injectable({
  providedIn: 'root'
})
export class ErrorHandlerService {

  constructor(private dialogService: NbDialogService, private router: Router) { }

  handle (error?: any, urlRedirect?: any){
    let message =  error.error.message ? error.error.message : 'Ha habido un error. Reintente la operación'
    this.dialogService.open(ErrorDialogComponent, {
      context: {
        msg: message,
      },
    })
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigateByUrl(urlRedirect);
      }
    });
  }
}
