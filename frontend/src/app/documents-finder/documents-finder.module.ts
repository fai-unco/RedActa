import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DocumentsFinderComponent } from './documents-finder.component';
import { DocumentsFinderRoutingModule } from './documents-finder-routing.module';
import { NbButtonModule, NbCardModule, NbDatepickerModule, NbIconModule, NbInputModule, NbListModule, NbSelectModule, NbSpinnerModule, NbTooltipComponent, NbTooltipModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';


@NgModule({
  declarations: [
    DocumentsFinderComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    DocumentsFinderRoutingModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbListModule,
    NbSpinnerModule,
    NbDatepickerModule,
    NbMomentDateModule,
    NbSelectModule,
    NbIconModule,
    NbTooltipModule
  ]
})
export class DocumentsFinderModule { }
