import { TestBed } from '@angular/core/testing';

import { UsersChangesNotifierService } from './users-changes-notifier.service';

describe('UsersChangesNotifierService', () => {
  let service: UsersChangesNotifierService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(UsersChangesNotifierService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
