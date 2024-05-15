import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentStampsComponent } from './document-stamps.component';

describe('DocumentStampsComponent', () => {
  let component: DocumentStampsComponent;
  let fixture: ComponentFixture<DocumentStampsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentStampsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentStampsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
