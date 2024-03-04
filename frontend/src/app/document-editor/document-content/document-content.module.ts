import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DocumentContentComponent } from './document-content.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';
import { NbInputModule, NbCardModule, NbButtonModule, NbActionsModule, NbUserModule, NbCheckboxModule, NbRadioModule, NbDatepickerModule, NbSelectModule, NbIconModule, NbTooltipModule, NbSpinnerModule, NbContextMenuModule, NbTabsetModule, NbDialogModule } from '@nebular/theme';
import { DocumentEditorRoutingModule } from '../../document-editor/document-editor-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { AnexoComponent } from './anexo/anexo.component';
import { InitSettingsDialogComponent } from './init-settings-dialog/init-settings-dialog.component';



@NgModule({
  declarations: [
    DocumentContentComponent,
    AnexoComponent,
    InitSettingsDialogComponent
  ],
  imports: [
    CommonModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
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
    NbTooltipModule,
    NbMomentDateModule,
    NbSpinnerModule,
    NbDialogModule.forChild(),
    NbContextMenuModule,
    SharedModule,
    NbTabsetModule,
  ], 
  exports: [
    DocumentContentComponent
  ]
})
export class DocumentContentModule { }
