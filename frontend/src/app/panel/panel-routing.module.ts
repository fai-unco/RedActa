import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PanelComponent } from './panel.component';

const routes: Routes = [
  {
    path: '', component: PanelComponent,
    children: [
      {
        path: 'documentos',
        children: [
          { 
            path: 'editar',
            loadChildren: () => import('../document-editor/document-editor.module').then(m => m.DocumentEditorModule) 
          },
          { 
            path: 'buscar',
            loadChildren: () => import('../document-finder/document-finder.module').then(m => m.DocumentFinderModule) 
          }
        ]
      }
    ]
  }
];
  
  

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PanelRoutingModule { }
