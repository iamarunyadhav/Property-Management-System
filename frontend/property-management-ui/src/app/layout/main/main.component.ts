import { Component } from '@angular/core';
import { AuthService } from '../../../app/core/auth.service';
import { CommonModule } from '@angular/common';

@Component({
  standalone: true,
  selector: 'app-main-layout',
  templateUrl: './main.component.html',
  imports: [CommonModule],
})
export class MainComponent {
  constructor(private auth: AuthService) {}

  logout(): void {
    this.auth.logout();
  }
}
