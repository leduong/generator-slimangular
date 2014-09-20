<?php

use Phpmig\Migration\Migration;

class Create<%= _.capitalize(pluralize(name)) %> extends Migration
{
    protected $tableName;

    /* @var \Illuminate\Database\Schema\Builder $schema */
    protected $schema;

    public function init()
    {
        $this->tableName = '<%= pluralize(name) %>';
        $this->schema = $this->get('schema');
    }

    /**
     * Do the migration
     */
    public function up()
    {
        /* @var \Illuminate\Database\Schema\Blueprint $table */
        $this->schema->create($this->tableName, function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            <% _.each(attrs, function (attr) { %>
            $table-><% 
            if ((attr.attrType == 'Enum')||(attr.attrType == 'Password')||(attr.attrType == 'Email')) { %>string<% } 
            else { %><%= attr.attrType.toLowerCase() %><% } 
            %>('<%= attr.attrName.replace(" ", "_").toLowerCase() %>')<%
                if (attr.attrName != "Text") { 
                    if (attr.maxLength > 0) { %>->length('<%= attr.maxLength %>')<% }
                    if (attr.attrName == "Integer") { %>->unsigned()<%}
                }
            %>;<%}); %>
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->schema->drop($this->tableName);
    }
}
