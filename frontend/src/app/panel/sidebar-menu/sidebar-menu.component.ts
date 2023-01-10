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
      title: 'Declaraciones',
      //expanded: true,
      children: [
        {
          title: 'Crear',
        },
        {
          title: 'Editar',
        },
        {
          title: 'Subir dec. firmada',
        },
      ],
    },
    {
      title: 'Resoluciones',
      children: [
        {
          title: 'Crear',
        },
        {
          title: 'Editar',
        },
        {
          title: 'Subir firmada',
        },
      ],
    },
    {
      title: 'Notas',
      children: [
        {
          title: 'Accion 1',
        },
        {
          title: 'Accion 2',
        },
        {
          title: 'Accion 3',
        },
      ],
    },
    {
      title: 'Memos',
      children: [
        {
          title: 'Accion 1',
        },
        {
          title: 'Accion 2',
        },
        {
          title: 'Accion 3',
        },
      ],
    },
    {
      title: 'Actas',
      children: [
        {
          title: 'Accion 1',
        },
        {
          title: 'Accion 2',
        },
        {
          title: 'Accion 3',
        },
      ],
    },
  ];

  constructor() { }

  ngOnInit(): void {
  }

}
