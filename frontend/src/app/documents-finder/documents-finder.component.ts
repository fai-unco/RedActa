import { Component, OnInit } from '@angular/core';
import { ApiConnectionService } from '../api-connection.service';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { finalize, forkJoin} from 'rxjs';
import { ErrorDialogComponent } from '../shared/error-dialog/error-dialog.component';
import { NbDialogService } from '@nebular/theme';
import { Router } from '@angular/router';
import { DatePipe } from '@angular/common'

@Component({
  selector: 'app-documents-finder',
  templateUrl: './documents-finder.component.html',
  styleUrls: ['./documents-finder.component.scss']
})
export class DocumentsFinderComponent implements OnInit {

  documents: any = null; 
  loadingResults = false;
  searchForm!: FormGroup;
  documentTypes!: any;
  issuers!: any;
  loadingForm!: boolean;
  
  constructor(private connectionService: ApiConnectionService, 
              private fb: FormBuilder, 
              private dialogService: NbDialogService,
              private router: Router,
              private datePipe: DatePipe) { }

  ngOnInit(): void {
    this.loadingForm = true;
    forkJoin([this.connectionService.get('document_types'), this.connectionService.get('issuers')])
    .pipe(finalize(() => this.loadingForm = false))
    .subscribe({
        next: (results: any) => {
          this.documentTypes = results[0].data;
          this.issuers = results[1].data;  
        }, 
        error: (e) =>{
          this.errorHandler(e, '/');
        }
    }),
    this.searchForm = this.fb.group({
      number: this.fb.control(''),
      name: this.fb.control(''),
      documentTypeId: this.fb.control(''),
      issuerId: this.fb.control(''),
      issueDateStart: this.fb.control(''),
      issueDateEnd: this.fb.control('')
    });
       
  }

  private formatDate(date: any){
    return this.datePipe.transform(date, 'yyyy-MM-dd');
  }

  search(){
    if(new Date(this.searchForm.get('issueDateStart')?.value) > new Date (this.searchForm.get('issueDateEnd')?.value)){
      this.errorHandler({error:{message: 'La fecha "desde" no puede ser posterior a la fecha "hasta"'}});
    } else{
      this.documents = null;
      let url = 'documents/search?'; 
      let data = this.searchForm.value;
      this.loadingResults = true;
      data['issueDateStart'] = this.formatDate(data['issueDateStart']);
      data['issueDateEnd'] = this.formatDate(data['issueDateEnd']);
      for(let [key, value] of Object.entries(data)){
        if(value && value != '*'){
          url = url+`${key}=${value}&`;
        }
      }
      this.connectionService.get(url)
        .pipe(
          finalize(() => this.loadingResults = false)
        )
        .subscribe({
          next: (res) => {
            this.documents = res;
          },
          error: (e) => {
            this.errorHandler(e);
          }
        })
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
