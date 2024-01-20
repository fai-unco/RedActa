import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DocumentEditorRoutingModule } from './document-editor-routing.module';
import { DocumentEditorComponent } from './document-editor.component';
import { NbInputModule, NbCardModule, NbButtonModule, NbActionsModule, NbUserModule, NbCheckboxModule, NbRadioModule, NbDatepickerModule, NbSelectModule, NbIconModule, NbTooltipModule, NbSpinnerModule, NbDialogModule, NbContextMenuModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';
import { AnexoComponent } from './anexo/anexo.component';
import { InitSettingsDialogComponent } from './init-settings-dialog/init-settings-dialog.component';
import { SharedModule } from '../shared/shared.module';


@NgModule({
  declarations: [
    DocumentEditorComponent,
    AnexoComponent,
    InitSettingsDialogComponent
  ],
  imports: [
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
    SharedModule
  ]
})
export class DocumentEditorModule { }
