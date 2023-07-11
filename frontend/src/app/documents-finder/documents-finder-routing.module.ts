import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DocumentsFinderComponent } from './documents-finder.component';

const routes: Routes = [{ path: '', component: DocumentsFinderComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DocumentsFinderRoutingModule { }