import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentsFinderComponent } from './documents-finder.component';

describe('DocumentsFinderComponent', () => {
  let component: DocumentsFinderComponent;
  let fixture: ComponentFixture<DocumentsFinderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentsFinderComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentsFinderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
