import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DocumentEditorComponent } from './document-editor.component';

const routes: Routes = [{ path: '', component: DocumentEditorComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DocumentEditorRoutingModule { }
