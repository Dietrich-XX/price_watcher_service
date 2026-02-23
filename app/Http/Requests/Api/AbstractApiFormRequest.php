<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Data;

/**
 * @template TDto of Data
 */
abstract class AbstractApiFormRequest extends FormRequest
{
	/**
     * Returns the fully qualified DTO class name
     *
	 * @return class-string<TDto>
	 */
	abstract protected function dtoClass(): string;

	/**
     * Create and return a DTO instance from validated request data
     *
	 * @return Data
     */
	public function getDto(): Data
    {
		$dtoClass = $this->dtoClass();

        /** @var Data $dto */
        return $dtoClass::from($this->validated());
	}

	/**
	 * Returns a list of custom validator classes to be executed after the main validation
	 *
	 * @return array
	 */
	protected function validators(): array
	{
		return [];
	}

	/**
	 * Registers additional custom validators to be executed after the main validation process.
	 * It allows extending validation logic beyond the default rules
	 *
	 * @param Validator $validator
	 * @return void
	 */
	public function withValidator(Validator $validator): void
	{
		foreach ($this->validators() as $customValidator) {
			$validator->after(fn (Validator $validator)  => app($customValidator)
				->validate($validator)
			);
		}
	}
}
