import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-document-editor',
  templateUrl: './document-editor.component.html',
  styleUrls: ['./document-editor.component.scss']
})
export class DocumentEditorComponent implements OnInit {

  remitentes = [
    {
      name: "Remitente 1",
      id: "idRemitente1"
    },
    {
      name: "Remitente 2",
      id: "idRemitente2"
    },
    {
      name: "Remitente 3",
      id: "idRemitente3"
    },
    {
      name: "Remitente 4",
      id: "idRemitente4"
    },
    {
      name: "Remitente 5",
      id: "idRemitente5"
    }
  ];
  
  documentType: string = "";


  constructor() { }

  ngOnInit(): void {
  }

}
