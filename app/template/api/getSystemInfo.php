{
	"status": "{{status}}",
	"system": {{system}},
	"connections": [
{{connections}}
	]

	"system": {
		"id": 1,
		"name": "VNU-438",
		"type": "V-A",
		"typeLink": "https...",
		"typeDesc": "Estrellas enanas de la secuencia principal",
		"discover": "dunedain",
		"radius": 5000,
		"numNPC": 2,
		"planets": [
			{
				"id": 2,
				"name": "VNU-438-1",
				"type": "Planeta esteril",
				"typeLink": "https...",
				"distance": 6,
				"radius": 6754,
				"rotation": 87,
				"explored": false,
				"exploreTime": 236,
				"resources": [
					{
						"type": 1,
						"name": "Hidrogeno",
						"value": 230
					}
				],
				"moons": [
					{
						"id": 3,
						"name": "VNU-438-1-1",
						"type": "Luna",
						"typeLink": "https...",
						"distance": 6,
						"radius": 6754,
						"rotation": 87,
						"explored": false,
						"exploreTime": 236,
						"resources": [
							{
								"type": 1,
								"name": "Hidrogeno",
								"value": 230
							}
						]
					}
				]
			}
		]
	},
	"connections": [
		{
			"idSystemEnd": 1,
			"order": 1,
			"navigateTime": 123,
			"name": "LDT-107"
		},
		{
			"idSystemEnd": null,
			"order": 2,
			"navigateTime": 234,
			"name": null
		}
	]
}