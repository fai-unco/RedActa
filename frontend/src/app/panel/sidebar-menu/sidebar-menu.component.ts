import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';
import { NbMenuItem } from '@nebular/theme';

@Component({
  selector: 'app-sidebar-menu',
  templateUrl: './sidebar-menu.component.html',
  styleUrls: ['./sidebar-menu.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class SidebarMenuComponent implements OnInit {

  items: NbMenuItem[]  = [
    {
      title: 'Crear documento',
      link: 'documentos/editar'
      //expanded: true,
    }
  ];

  constructor() { }

  ngOnInit(): void {
  }

}
