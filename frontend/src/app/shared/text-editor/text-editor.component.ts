import {
  ChangeDetectorRef,
  Component,
  ElementRef,
  Input,
  OnInit,
  ViewChild,
} from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

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

export class TextEditorComponent implements OnInit, ControlValueAccessor {
  @Input('placeholder') placeholder: string = "";
  @ViewChild('container') container!: ElementRef;
  onTouched = () => {};
  touched = false;
  disabled = false;
  content: string = '';
  onChange = (content: any) => {this.content = content};
  render: boolean = true;
  callback =  (cb: any, value: any, meta: any) => {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.onchange = function (e?: any) {
      let file = e.target.files[0];
      let reader: any = new FileReader();
      reader.onload = function (event: any) {
        //var id = 'blobid' + (new Date()).getTime();
        var blobCache =  event.target.result;
        //var base64 = reader.result.split(',')[1];
        var blobInfo = blobCache;
        //blobCache.add(blobInfo);
        cb(blobInfo, { title: file.name });
      };
      reader.readAsDataURL(file);
    };

    input.click();
  }

  ngOnInit(): void { 
    if (window.addEventListener) {
      window.addEventListener("uiTheme", _ => {
        this.render = false;
        this.changeDetectorRef.detectChanges();
        this.render = true;
      });
    }
  }

  get skin() {
    return localStorage.getItem('uiTheme') == 'dark'? 'oxide-dark' : 'oxide';
  }

  get contentCss() {
    return localStorage.getItem('uiTheme') == 'dark'? 'dark' : 'default';
  }

  constructor(private changeDetectorRef: ChangeDetectorRef) {
  }

  onTextChange(e: any){
    this.markAsTouched();
    if (!this.disabled) {
      this.onChange(this.content);
    }
  }

  writeValue(content: any) {
    this.content = content;
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