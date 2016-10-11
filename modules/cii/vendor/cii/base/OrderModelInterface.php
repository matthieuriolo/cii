<?php

namespace cii\base;


interface OrderModelInterface {
	public function next();
	public function previous();

	public function orderUp();
	public function orderDown();
}