import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DocumentFinderComponent } from './document-finder.component';

const routes: Routes = [{ path: '', component: DocumentFinderComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DocumentFinderRoutingModule { }
