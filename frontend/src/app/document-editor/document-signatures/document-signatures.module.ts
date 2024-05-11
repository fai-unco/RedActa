import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { StampSelectorComponent } from './signature-selector/signature-selector.component';
import { DocumentStampsComponent } from './document-signatures.component';
import { NbButtonModule, NbCardModule, NbContextMenuModule, NbDialogModule, NbIconModule, NbInputModule, NbListModule, NbSelectModule, NbSpinnerModule, NbTooltipModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '../../shared/shared.module';



@NgModule({
  declarations: [
    DocumentStampsComponent,
    StampSelectorComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbSelectModule,
    NbIconModule,
    NbSpinnerModule,
    NbDialogModule.forChild(),
    NbListModule,
    SharedModule,
    NbTooltipModule,
    NbContextMenuModule,
  ],
  exports: [
    DocumentStampsComponent
  ]
})
export class DocumentStampsModule { }
