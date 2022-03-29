<?php 
namespace App\Filters;
use App\Models\Idea;

/**
 * 
 */
class IdeaFilter extends Filters
{
	protected $filters = [
		'category_id'
	];
	
	public function category_id($value)
	{
		if ($value == 'name') 
		{
			return $this->name();
		}elseif ($value == 'asc') 
		{
			return $this->id($value);
		}elseif ($value == 'desc') 
		{
			return $this->id($value);
		}elseif ($value == 'like') 
		{
			return $this->like();
		}elseif ($value == 'comment') 
		{
			return $this->comment();
		}elseif ($value == 'all') {
			return $this->builder->inRandomOrder();
		}
		
	}

	protected function name()
	{
		return $this->builder->join('departments', 'departments.id','=','ideas.department_id')
				->orderBy('departments.code', 'asc');
	}

	protected function id($param)
	{
		return $this->builder->orderBy('id',$param);
	}

	protected function like()
	{
		return $this->builder->withCount('reactions')->orderBy('reactions_count','desc');
	}

	protected function comment()
	{
		return $this->builder->withCount('comments')->orderBy('comments_count','desc');
	}

}

?>