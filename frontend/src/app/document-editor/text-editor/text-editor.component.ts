import {
  AfterViewInit,
  Component,
  ElementRef,
  OnInit,
  ViewChild,
} from '@angular/core';
import Quill from 'quill';
import QuillCursors from 'quill-cursors';

@Component({
  selector: 'app-text-editor',
  templateUrl: './text-editor.component.html',
  styleUrls: ['./text-editor.component.scss'],
})
export class TextEditorComponent implements AfterViewInit {
  @ViewChild('container') container!: ElementRef;

  constructor() {}

  ngAfterViewInit(): void {
    Quill.register('modules/cursors', QuillCursors);
    const quill = new Quill(this.container.nativeElement, {
      modules: {
        cursors: true,
        toolbar: [
          // adding some basic Quill content features
          //[{ header: [1, 2, false] }],
          ['bold', 'italic'],

          [
            //{ 'list': 'ordered'},
            { list: 'bullet' },
          ],
          //['link'],
          //, 'underline'],
          //[{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }]
        ],
        history: {
          // Local undo shouldn't undo changes
          // from remote users
          userOnly: true,
        },
      },
      placeholder: 'Start writing',
      theme: 'snow', // 'bubble' is also great
    });
  }
}
