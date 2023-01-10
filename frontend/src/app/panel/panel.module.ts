import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PanelRoutingModule } from './panel-routing.module';
import { PanelComponent } from './panel.component';
import { NbContextMenuModule, NbLayoutModule, NbSidebarModule } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';

@NgModule({
  declarations: [
    PanelComponent
  ],
  imports: [
    CommonModule,
    PanelRoutingModule,
    NbContextMenuModule,
    NbLayoutModule,
    NbEvaIconsModule,
    NbSidebarModule


  ]
})
export class PanelModule { }
