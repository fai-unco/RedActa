import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DocumentEditorRoutingModule } from './document-editor-routing.module';
import { DocumentEditorComponent } from './document-editor.component';
import { NbInputModule, NbCardModule, NbButtonModule, NbActionsModule, NbUserModule, NbCheckboxModule, NbRadioModule, NbDatepickerModule, NbSelectModule, NbIconModule } from '@nebular/theme';


@NgModule({
  declarations: [
    DocumentEditorComponent
  ],
  imports: [
    CommonModule,
    DocumentEditorRoutingModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbActionsModule,
    NbUserModule,
    NbCheckboxModule,
    NbRadioModule,
    NbDatepickerModule,
    NbSelectModule,
    NbIconModule,
  ]
})
export class DocumentEditorModule { }
