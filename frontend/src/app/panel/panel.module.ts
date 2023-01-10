import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PanelRoutingModule } from './panel-routing.module';
import { PanelComponent } from './panel.component';
import { NbContextMenuModule, NbLayoutModule, NbMenuModule, NbSidebarModule } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';
import { SidebarMenuComponent } from './sidebar-menu/sidebar-menu.component';

@NgModule({
  declarations: [
    PanelComponent,
    SidebarMenuComponent
  ],
  imports: [
    CommonModule,
    PanelRoutingModule,
    NbContextMenuModule,
    NbLayoutModule,
    NbEvaIconsModule,
    NbSidebarModule,
    NbMenuModule


  ]
})
export class PanelModule { }
