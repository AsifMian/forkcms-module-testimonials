{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblBlog|ucfirst}: {$lblArticles}</h2>

	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">
			<span>{$lblAdd|ucfirst}</span>
		</a>
	</div>
</div>

{option:dgNotYetPublished}
	<div class="dataGridHolder">
		<div class="tableHeading">
			<h3>{$lblNotYetPublished|ucfirst}</h3>
		</div>
		{$dgNotYetPublished}
	</div>
{/option:dgNotYetPublished}

{option:dgPublished}
	<div class="dataGridHolder">
		<div class="tableHeading">
			<h3>{$lblPublished|ucfirst}</h3>
		</div>
		{$dgPublished}
	</div>
{/option:dgPublished}

{option:noItems}<p>{$msgNoItems}</p>{/option:noItems}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}