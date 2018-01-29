<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\MemberProject;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MemberProjectControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function testListMemberProjectSuccess()
    {
        $project = factory(MemberProject::class)->create();
        $response = $this->get('member_projects');
        $response->assertStatus(200);
    }

    public function testDeleteMemberProjectSuccess()
    {
        $json = '{"message":"Delete success 1"}';
        $array = Factory(MemberProject::class)->create()->toArray();
        $response = $this->call('DELETE', 'member_projects/destroy', $array);
        $this->assertEquals(200, $response->status());
        $this->assertSame($json, $response->getContent());
        $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
        ]);
    }

    public function testEditMemberProjectSuccess()
    {
        $json = '{"id":1,"member_id":1,"project_id":1,"role":"pm"}';
        $array1 = [
            'id'=>1,
            'member_id'=>1,
            'project_id'=>1,
            'role'=>'pm',
        ];
        $array = Factory(MemberProject::class)->create()->toArray();
        $response=$this->json('PUT', 'member_projects/update', $array1);
        $this->assertSame($json, $response->getContent());
        $response->assertStatus(200, $response->status());
        $response->assertSuccessful();
    }

    public function testAddMemberProjecSuccess()
    {
        $json = '{"member_id":1,"project_id":1,"role":"pm","id":1}';
        $array1 = [
            'id'=>1,
            'member_id'=>1,
            'project_id'=>1,
            'role'=>'pm',
        ];
        $response = $this->json('POST', 'member_projects/create', $array1);
        $this->assertSame($json, $response->getContent());
        $response->assertStatus(200, $response->status());
        $response->assertSuccessful();
    }

    public function testAddMemberProjecSuccessWithRoleNull()
    {
        $json = '{"member_id":1,"project_id":1,"role":"","id":1}';
        $array1 = [
            'id'=>1,
            'member_id'=>1,
            'project_id'=>1,
            'role'=>'',
        ];
        $response = $this->json('POST', 'member_projects/create', $array1);
        $this->assertSame($json, $response->getContent());
        $response->assertStatus(200, $response->status());
        $this->assertSame($json, $response->getContent());
        $response->assertSuccessful();
    }

    public function testAddMemberProjectWithMemberIdRequired()
    {
        $json = '{"message":"The given data was invalid.","errors":{"member_id":["The member id field is required."]}}';
        $array = [
            'member_id'=>'',
            'project_id'=>1,
            'role'=>'pm',
        ];
        $response = $this->json('POST', 'member_projects/create', $array);
        $response->assertStatus(422, $response->status());
        $this->assertSame($json, $response->getContent());
        $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
            ]);
    }

    public function testAddMemberProjectWithProjectIdRequired()
    {
        $json = '{"message":"The given data was invalid.","errors":{"project_id":["The project id field is required."]}}';
        $array = [
        'member_id'=>1,
        'project_id'=>'',
        'role'=>'pm',
        ];
        $response = $this->json('POST', 'member_projects/create', $array);
        $response->assertStatus(422, $response->status());
        $this->assertSame($json, $response->getContent());
        $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
            ]);
    }

    public function testAddMemberProjectWithNotValidMemberId()
    {
        $json = '{"message":"The given data was invalid.","errors":{"member_id":["The member id must be an integer."]}}';
        $array = [
            'member_id'=>'eeee',
            'project_id'=>1,
            'role'=>'pm',
        ];
        $response=$this->json('POST', 'member_projects/create', $array);
        $response->assertStatus(422, $response->status());
         $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
           ]);
        $this->assertSame($json, $response->getContent());
    }

    public function testAddMemberProjectWithNotValidProjectId()
    {
        $json = '{"message":"The given data was invalid.","errors":{"project_id":["The project id must be an integer."]}}';
        $array = [
            'member_id'=>1,
            'project_id'=>'ddd',
            'role'=>'pm',
        ];
        $response = $this->json('POST', 'member_projects/create', $array);
        $response->assertStatus(422, $response->status());
         $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
           ]);
         $this->assertSame($json, $response->getContent());
    }

    public function testEditMemberProjectWithNotValidMemberId()
    {
        $json = '{"message":"The given data was invalid.","errors":{"member_id":["The member id must be an integer."]}}';
        $array = [
            'member_id'=>'eeee',
            'project_id'=>1,
            'role'=>'pm',
        ];
        $response = $this->json('PUT', 'member_projects/update', $array);
        $response->assertStatus(422, $response->status());
         $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
           ]);
         $this->assertSame($json, $response->getContent());
    }

    public function testEditMemberProjectWithNotValidProjectId()
    {
        $json = '{"message":"The given data was invalid.","errors":{"project_id":["The project id must be an integer."]}}';
        $array = [
            'member_id'=>1,
            'project_id'=>'ddd',
            'role'=>'pm',
        ];
        $response = $this->json('PUT', 'member_projects/update', $array);
        $response->assertStatus(422, $response->status());
         $this->assertDatabaseMissing('projects', [
            'member_id'=>$array['member_id'],
            'project_id'=>$array['project_id'],
            'role'=>$array['role'],
           ]);
         $this->assertSame($json, $response->getContent());
    }
}