import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentSignaturesComponent } from './document-signatures.component';

describe('DocumentSignaturesComponent', () => {
  let component: DocumentSignaturesComponent;
  let fixture: ComponentFixture<DocumentSignaturesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentSignaturesComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentSignaturesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
