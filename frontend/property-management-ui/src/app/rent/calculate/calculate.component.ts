import { Component, OnInit } from '@angular/core';
import { RentService } from '../../../../src/app/core/rent.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-calculate',
  templateUrl: './calculate.component.html',
  standalone: true,
  imports: [CommonModule],
})
export class CalculateComponent implements OnInit {
  results: any[] = [];

  constructor(private rentService: RentService) {}

  ngOnInit(): void {
    this.fetchRent();
  }

  fetchRent(): void {
    this.rentService.calculateRent().subscribe((res: any) => {
      this.results = res.data;
    });
  }
}
