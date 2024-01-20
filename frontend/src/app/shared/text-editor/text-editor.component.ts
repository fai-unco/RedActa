import {
  AfterViewInit,
  Component,
  ElementRef,
  Input,
  OnInit,
  ViewChild,
} from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';
import Quill from 'quill';
import QuillCursors from 'quill-cursors';

@Component({
  selector: 'app-text-editor',
  templateUrl: './text-editor.component.html',
  styleUrls: ['./text-editor.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      multi:true,
      useExisting: TextEditorComponent
    }]
  })
export class TextEditorComponent implements AfterViewInit, ControlValueAccessor {
  @Input('placeholder') placeholder: string = "";
  @ViewChild('container') container!: ElementRef;
  quillInstance: any;
  onChange = (content: any) => {};
  onTouched = () => {};
  touched = false;
  disabled = false;
  content: string ="";

  constructor() {}

  ngAfterViewInit(): void {
    Quill.register('modules/cursors', QuillCursors);
    this.quillInstance = new Quill(this.container.nativeElement, {
      modules: {
        cursors: true,
        toolbar: [
          // adding some basic Quill content features
          //[{ header: [1, 2, false] }],
          ['bold', 'italic', 'underline'],

          [
            //{ 'list': 'ordered'},
            { list: 'bullet' },
          ],
          //['link'],
          
          //[{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }]
        ],
        history: {
          // Local undo shouldn't undo changes
          // from remote users
          userOnly: true,
        },
      },
      placeholder: this.placeholder,
      theme: 'snow', // 'bubble' is also great
    });

    this.quillInstance.on('text-change', (delta: any, oldDelta: any, source: any) => {
      this.onTextChange();
    });

    this.quillInstance.root.innerHTML = this.content;
  }

  private onTextChange(){
    this.content = this.quillInstance.root.innerHTML;
    this.markAsTouched();
    if (!this.disabled) {
      this.onChange(this.content);
    }
  }

  writeValue(content: any) {
    this.content = content;
    if(this.quillInstance?.root){
      this.quillInstance.root.innerHTML = content;
    }
  }

  registerOnChange(onChange: any) {
    this.onChange = onChange;
  }

  registerOnTouched(onTouched: any) {
    this.onTouched = onTouched;
  }

  markAsTouched() {
    if (!this.touched) {
      this.onTouched();
      this.touched = true;
    }
  }

  setDisabledState(disabled: boolean) {
    this.disabled = disabled;
  }
}


